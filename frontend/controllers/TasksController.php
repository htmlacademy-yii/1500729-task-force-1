<?php


namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\Files;
use frontend\models\FilterTasks;
use frontend\models\FilterUsers;
use frontend\models\Reviews;
use frontend\models\TaskForm;
use frontend\models\Responds;
use frontend\models\TaskFiles;
use frontend\models\Tasks;
use frontend\models\Users;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use taskforce\app\Task;
use Yii;
use yii\base\Event;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;


class TasksController extends SecuredController
{
    public function behaviors()
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
        if ($this->action->id == 'upload') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function actionIndex()
    {


        $get = Yii::$app->request->get();
        $model = new FilterTasks();
        $model->load($get);
        $categories = Categories::find()->all();

        $tasks = Tasks::find()->where(['status' => Tasks::STATUS_NEW])
            ->orderBy(['dt_add' => SORT_DESC])
            ->with('category')
            ->with('location')->joinWith('responds');
        if ($model->load(Yii::$app->request->get())) {
            if ($model->category_id) {
                $tasks = $tasks->andFilterWhere(['IN', 'category_id', $model->category_id]);
            }
            if ($model->search) {
                $model->options = NULL;
                $model->category_id = NULL;
                $model->period = NULL;
                $tasks = $tasks->andFilterWhere(['LIKE', 'title', $model->search]);
            }

            if ($model->options && ArrayHelper::isIn(1, $model->options)) {
                $tasks = $tasks->andFilterWhere($model->getTasksWithoutResponds());
            }
            if ($model->options && ArrayHelper::isIn(2, $model->options)) {
                $tasks = $tasks->andFilterWhere($model->getRemoteTasks());
            }
            if ($model->period) {
                $tasks = $tasks->andFilterWhere($model->getPeriod());
            }
        }

        $tasks = $tasks->all();
        return $this->render('tasks', ['tasks' => $tasks, 'model' => $model, 'categories' => $categories]);
    }

    public function actionView($id)
    {
        $user_id = Yii::$app->user->identity->getId();
        $review = new Reviews();
        $taskDone = new Tasks();

        $task = Tasks::find()->where(['id' => $id])->with('category')
            ->with('taskFiles.file')->with('location')->with('author')->one();
        if (!$task) {
            throw new NotFoundHttpException("Задание с ID {$id} не найдено");
        }

        $_task = new Task($task->author_id, $task->executor_id);
        $respondAuthor = Responds::find()->where(['task_id' => $task->id])->andWhere(['executor_id' => Yii::$app->user->identity->getId()])->one();
        $model = new Responds();
        if (!$respondAuthor) {
            $model->task_id = $id;
            $model->executor_id = Yii::$app->user->identity->getId();
            if (Yii::$app->request->post()) {
                $model->load(Yii::$app->request->post());
                if ($model->validate()) {
                    $model->save();
                }
                return $this->redirect(['tasks/view', 'id' => $id]);
            }
        }
        return $this->render('view', ['task' => $task,
            'model' => $model,
            '_task' => $_task,
            'respondAuthor' => $respondAuthor,
            'user_id' => $user_id,
            'review' => $review,
            'taskDone' => $taskDone]);

    }

    public function actionCreate()
    {
        $taskForm = new TaskForm();
        $categories = Categories::find()->all();

        if (Yii::$app->request->post()) {
            $taskForm->load(Yii::$app->request->post());

            if ($taskForm->validate()) {
                $id = $taskForm->createTask();
                if (Yii::$app->session->get('files')) {
                    $files = Yii::$app->session->get('files');
                    foreach ($files as $name => $file) {
                        $file_path = new Files();
                        $file_path->path = '/uploads/' . $file;
                        $file_path->name = $name;
                        $file_path->save();
                        $task_file = new TaskFiles();
                        $task_file->file_id = $file_path->id;
                        $task_file->task_id = $id;
                        $task_file->save();
                    }
                    Yii::$app->session->remove('files');
                }

                return $this->redirect(['tasks/view', 'id' => $id]);
            }
        }
        Yii::$app->session->remove('files');
        return $this->render('create', ['taskForm' => $taskForm, 'categories' => $categories]);
    }

    public function actionUpload()
    {


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
    }

    public function actionChoose($taskId, $executorId): Response
    {
        $task = Tasks::findOne($taskId);
        $task->status = Task::STATUS_IN_WORK;
        $task->executor_id = $executorId;
        $task->save();
        return $this->goHome();
    }

    public function actionRefuse($taskId, $executorId)
    {
        $task = Tasks::findOne($taskId);
        $task->status = Task::STATUS_FAILED;
        $task->save();
        $executorId = Users::findOne($executorId);
        if ($executorId->failed_tasks) {
            $executorId->updateCounters(['failed_tasks' => 1]);
        } else {
            $executorId->failed_tasks = 1;
            $executorId->save();
        }
        return $this->redirect(['tasks/view', 'id' => $taskId]);
    }

    public function actionDone($taskId)
    {
        $task = Tasks::findOne($taskId);
        $review = new Reviews();
        $review->task_id = $taskId;
        if ($task->author_id == Yii::$app->user->id) {
            if (Yii::$app->request->post()) {
                $review->load(Yii::$app->request->post());
                $task->load(Yii::$app->request->post());
                if ($review->validate()) {
                    $task->save();
                    $review->save();

                    if ($task->status == Task::STATUS_DONE) {
                        if ($task->executor->done_tasks) {
                            $task->executor->updateCounters(['done_tasks' => 1]);
                        } else {
                            $task->executor->done_tasks = 1;
                            $task->executor->save();
                        }
                    } elseif ($task->status == Task::STATUS_FAILED) {
                        if ($task->executor->failed_tasks) {
                            $task->executor->updateCounters(['failed_tasks' => 1]);
                        } else {
                            $task->executor->failed_tasks = 1;
                            $task->executor->save();
                        }
                    }
                    return $this->redirect(['tasks/view', 'id' => $taskId]);
                }
            }
        } else {
            throw new BadRequestHttpException('Нет доступа');
        }
    }

    public function actionDecline($respondId)
    {
        $respond = Responds::findOne($respondId);
        $respond->decline = 1;
        $respond->save();
        return $this->redirect(['tasks/view', 'id' => $respond->task_id]);
    }

    public function actionCancel($taskId)
    {
        $task = Tasks::findOne($taskId);
        $task->status = Task::STATUS_CANCEL;
        $task->save();
        return $this->redirect(['tasks/view', 'id' => $taskId]);
    }
}
