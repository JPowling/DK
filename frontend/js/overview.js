function trainsFocusOut() {
  var trainID = document.getElementById("trains_select").value;

  if (!is_valid_datalist_value("trains", trainID)) {
    return;
  }

  location.href = "/moderation/overview?view=f&id=" + trainID;
}

function trainsFocusIn() {
  document.getElementById("trains_select").value = "";
}

function stationsFocusOut() {
  var stationID = document.getElementById("stations_select").value;

  if (!is_valid_datalist_value("stations", stationID)) {
    return;
  }

  location.href = "/moderation/overview?view=b&id=" + stationID;
}

function stationsFocusIn() {
  document.getElementById("stations_select").value = "";
}

function routesFocusOut() {
  var routeID = document.getElementById("routes_select").value;

  if (!is_valid_datalist_value("routes", routeID)) {
    return;
  }

  location.href = "/moderation/overview?view=r&id=" + routeID;
}

function routesFocusIn() {
  document.getElementById("routes_select").value = "";
}

function is_valid_datalist_value(idDataList, inputValue) {
  var option = document.querySelector("#" + idDataList + " option[value='" + inputValue + "']");
  if (option != null) {
    return option.value.length > 0;
  }
  return false;
}
