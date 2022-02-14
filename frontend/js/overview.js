function trainsFocusOut() {
    var trainID = document.getElementById("trains_select").value;

    if (!is_valid_datalist_value("trains", trainID)) {
        return;
    }

    location.href = "/moderation/overview?view=f&id=" + trainID;
}

// https://stackoverflow.com/questions/24934669/how-can-i-validate-the-input-from-a-html5-datalist
function is_valid_datalist_value(idDataList, inputValue) {
    var option = document.querySelector("#" + idDataList + " option[value='" + inputValue + "']");
    if (option != null) {
      return option.value.length > 0;
    }
    return false;
  }
