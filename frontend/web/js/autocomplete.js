const autoCompleteJS = new autoComplete({
  wrapper: false,
  data: {
    src: async (query) => {
      try {
        const sourse = await fetch(`/geo/index?query=${query}`);
        return await sourse.json();
      } catch (error) {
        return error;
      }
    },
    keys: ["adress"]

  },
  searchEngine: "loose",
  debounce: 500,
  threshold: 5,
  events: {
    input: {
      selection: (event) => {
        const selection = event.detail.selection.match;
        const coordinates = event.detail.selection.value.coordinates;

        autoCompleteJS.input.value = selection;
        document.getElementById('coordinates').value = coordinates;


      }
    }
  }
});
document.querySelector("#autoComplete").addEventListener("response", function (event) {
  // "event.detail" carries the returned data values
  console.log(event.detail)
});
document.querySelector("#autoComplete").addEventListener("selection", function (event) {
  // "event.detail" carries the autoComplete.js "feedback" object
  console.log(event.detail);
});
