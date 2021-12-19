<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\ChooseExecutorForm;
use frontend\models\FilterTasks;
use frontend\models\RefuseTaskForm;
use frontend\models\Reviews;
use frontend\models\TaskForm;
use frontend\models\Responds;
use frontend\models\Tasks;
use frontend\models\Users;
use frontend\services\ChooseService;
use frontend\services\TaskCreateService;
use frontend\services\TaskDoneService;
use frontend\services\TaskFilterService;
use frontend\services\TaskRefuseService;
use frontend\services\TaskRespondService;
use taskforce\app\Task;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class TasksController extends SecuredController
{
    public function behaviors(): array
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['create'],
            'matchCallback' => function ($rules, $action) {
                if (Yii::$app->user->isGuest) {
                    return false;
                }

                return Yii::$app->user->identity->role != Users::ROLE_AUTHOR;
            },
            'denyCallback' => function ($rules, $action) {
                throw new BadRequestHttpException('Нет доступа');
            }
        ];
        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    public function beforeAction($action)
    {
        if ($this->action->id === 'upload') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionIndex(): string
    {
        $categories = Categories::find()->all();
        $model = new FilterTasks();
        $tasks = Tasks::find()->where(['status' => Tasks::STATUS_NEW])
            ->orderBy(['dt_add' => SORT_DESC])
            ->with('category')
            ->with('location')->joinWith('responds')->andWhere(['location_id' => [Yii::$app->session->get('location_id'), null]]);

        if (Yii::$app->request->get()) {
            $tasks = (new TaskFilterService())->filterTasks($tasks, Yii::$app->request->get(), $model);
        }

        $tasksProvider = new ActiveDataProvider([
            'query' => $tasks,
            'pagination' => [
                'pageSize' => 5
            ]
        ]);
        return $this->render('tasks', ['dataProvider' => $tasksProvider, 'model' => $model, 'categories' => $categories]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionView(int $id)
    {
        $user_id = Yii::$app->user->identity->getId();
        $review = new Reviews();
        $model = new Responds();
        $task = Tasks::find()->where(['id' => $id])->with('category')
            ->with('taskFiles.file')->with('location')->with('author')->one();

        if (!$task) {
            throw new NotFoundHttpException("Задание с ID {$id} не найдено");
        }

        $respondAuthor = Responds::find()->where(['task_id' => $task->id])->andWhere(['executor_id' => Yii::$app->user->identity->getId()])->one();

        if (Yii::$app->request->post()) {
            try {
                (new TaskRespondService())->execute($respondAuthor, $model, Yii::$app->request->post(), $id);
                return $this->redirect(['tasks/view', 'id' => $id]);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        return $this->render('view', ['task' => $task,
            'model' => $model,
            'respondAuthor' => $respondAuthor,
            'user_id' => $user_id,
            'review' => $review]);
    }

    /**
     * @return string|Response
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $taskForm = new TaskForm();
        $categories = Categories::find()->all();

        if (Yii::$app->request->post()) {
            try {
                $taskForm->load(Yii::$app->request->post());
                if ($taskForm->validate()) {
                    $taskId = (new TaskCreateService())->execute($taskForm);
                    return $this->redirect(['tasks/view', 'id' => $taskId]);
                }
            } catch (\yii\base\Exception $exception) {
                throw $exception;
            }
        }
        Yii::$app->session->remove('files');
        return $this->render('create', ['taskForm' => $taskForm, 'categories' => $categories]);
    }

    /**
     * @return \yii\console\Response|Response
     */
    public function actionUpload()
    {
        try {
            $files = UploadedFile::getInstanceByName('files');
            $name = $files->name;

            $filename = uniqid('upload') . '.' . $files->getExtension();
            $files->saveAs('@webroot/uploads/' . $filename);
            if (Yii::$app->session->get('files')) {
                $f = Yii::$app->session->get('files');
                $f[$name] = $filename;
                Yii::$app->session->set('files', $f);
            } else {
                $f[$name] = $filename;
                Yii::$app->session->set('files', $f);
            }
            $response = Yii::$app->response;
            $response->statusCode = 200;
            $response->data = 'Файл успешно загружен';
            return $response;
        } catch (\Exception $e) {
            $response = Yii::$app->response;
            $response->statusCode = 500;
            $response->data = 'Не удалось загрузить файл. Ошибка: ' . $e;
            $response->format = Response::FORMAT_JSON;
            return $response;
        }
    }

    /**
     * @param int $taskId
     * @param int $executorId
     * @return Response
     * @throws BadRequestHttpException
     */
    public function actionChoose(int $taskId, int $executorId): Response
    {
        $chooseExecutor = new ChooseExecutorForm();
        $task = Tasks::findOne($taskId);
        $chooseExecutor->author_id = $task->author_id;
        $chooseExecutor->status = $task->status;
        $chooseExecutor->user_id = Yii::$app->user->id;
        if ($chooseExecutor->validate()) {
            $task->status = Task::STATUS_IN_WORK;
            $task->executor_id = $executorId;
            $task->save();
            (new ChooseService())->sendNotification($taskId, $executorId);
            return $this->goHome();
        } else {
            throw new BadRequestHttpException($chooseExecutor->getFirstError('user_id'));
        }
    }

    /**
     * @param int $taskId
     * @param int $executorId
     * @return Response
     * @throws BadRequestHttpException
     */
    public function actionRefuse(int $taskId, int $executorId): Response
    {
        $refuseTask = new RefuseTaskForm();
        $task = Tasks::findOne($taskId);

        $refuseTask->executor_id = $executorId;
        $refuseTask->user_id = Yii::$app->user->id;
        $refuseTask->status = $task->status;

        if ($refuseTask->validate()) {
            (new TaskRefuseService())->execute($task, $executorId);
            return $this->redirect(['tasks/view', 'id' => $taskId]);
        } else {
            throw new BadRequestHttpException($refuseTask->getFirstError('user_id'));
        }
    }

    /**
     * @param int $taskId
     * @return Response
     * @throws \yii\base\Exception
     */
    public function actionDone(int $taskId): Response
    {
        try {
            if (!Yii::$app->request->post()) {
                throw new \yii\base\Exception('Некорректный запрос', 400);
            }
            (new TaskDoneService())->execute(Yii::$app->request->post(), $taskId);
            return $this->redirect(['tasks/view', 'id' => $taskId]);
        } catch (\yii\base\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param int $respondId
     * @return Response
     * @throws BadRequestHttpException
     */
    public function actionDecline(int $respondId): Response
    {
        $respond = Responds::findOne($respondId);
        $respond->decline = 1;
        if ($respond->validate()) {
            $respond->save();
            return $this->redirect(['tasks/view', 'id' => $respond->task_id]);
        } else {
            throw new BadRequestHttpException('Отказаться может только автор задания');
        }
    }

    /**
     * @param int $taskId
     * @return Response
     * @throws BadRequestHttpException
     */
    public function actionCancel(int $taskId): Response
    {
        $task = Tasks::findOne($taskId);
        if ($task->validateCancel()) {
            $task->status = Task::STATUS_CANCEL;
            $task->save();
            return $this->redirect(['tasks/view', 'id' => $taskId]);
        } else {
            throw new BadRequestHttpException('Вы не можете отменить эту задачу');
        }
    }
}
