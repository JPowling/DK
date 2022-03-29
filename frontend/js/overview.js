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

// const setDragging = (e) => {
//   console.log(e.target);
// }

function allowDrop(ev) {
  ev.preventDefault();
}

function drag(ev) {
  ev.dataTransfer.setData("id", ev.target.id);
}

function drop(ev) {
  ev.preventDefault();
  let fromID = ev.dataTransfer.getData("id");
  let toID = ev.target.id;

  console.log("Dragging " + fromID + " to " + toID);

  if (fromID != toID) {
    let table = document.getElementById("list").rows;
    let from = table[fromID];
    let to = table[toID];

    let last = table.length - 3;

    let toCache = to.innerHTML
      .replaceAll("\"" + toID + "\"", "\"" + fromID + "\"")
      .replaceAll("-" + toID, "-" + fromID);
    to.innerHTML = from.innerHTML
      .replaceAll("\"" + fromID + "\"", "\"" + toID + "\"")
      .replaceAll("-" + fromID, "-" + toID);

    from.innerHTML = toCache;

    let toStops = to.querySelector("#stops");
    let toTime = to.querySelector("#time");
    let toDelete = to.querySelector("#delete");
    let fromStops = from.querySelector("#stops");
    let fromTime = from.querySelector("#time");
    let fromDelete = from.querySelector("#delete");

    if (toID == 1 || toID == last) {
      toStops.innerHTML = "";
      toTime.innerHTML = "";
      toDelete.innerHTML = "";
    } else if (toStops.innerHTML === "") {
      toStops.innerHTML = "<input type=\"checkbox\" name=\"stands-" + toID + "\" checked=\"on\" />";
      toTime.innerHTML = "<input type=\"number\" name=\"duration-" + toID + "\" value=\"0\">";
      toDelete.innerHTML = "<p class=\"delete pointer\" onclick=\"deleteRoutePart(this)\" id=\"delete-" + $toID + "\">Delete</p>";
    }

    if (fromID == 1 || fromID == last) {
      fromStops.innerHTML = "";
      fromTime.innerHTML = "";
      fromDelete.innerHTML = "";
    } else if (fromStops.innerHTML === "") {
      fromStops.innerHTML = "<input type=\"checkbox\" name=\"stands-" + fromID + "\" checked=\"on\" />";
      fromTime.innerHTML = "<input type=\"number\" name=\"duration-" + fromID + "\" value=\"0\">";
      fromDelete.innerHTML = "<p class=\"delete pointer\" onclick=\"deleteRoutePart(this)\" id=\"delete-" + $fromID + "\">Delete</p>";
    }
  }
}

function deleteRoutePart(e) {
  let id = parseInt(e.id.replaceAll("delete-", ""));
  console.log("delete " + id);

  let t = document.getElementById("list");
  let table = t.rows;
  let last = table.length - 3;

  for (var i = id; i < last; i++) {
    table[i].innerHTML = table[i + 1].innerHTML
      .replaceAll("\"" + (i + 1) + "\"", "\"" + i + "\"")
      .replaceAll("-" + (i + 1), "-" + i);
  }

  t.deleteRow(last);

  table[last].innerHTML = "";
}

var template = "<th><p class=\"lightgray grabber\" id=\"{NUMBER}\" ondrop=\"drop(event)\" ondragover=\"allowDrop(event)\" draggable=\"true\""
+ "ondragstart=\"drag(event)\">Drag</p></th><th><a class=\"navigator-button\" href=\"/moderation/overview?view=b&amp;id={NAME}\""
+ ">{NAME}</a><input type=\"hidden\" name=\"short-{NUMBER}\" value=\"{NAME}\"/></th><th id=\"stops\"><input type=\"checkbox\""
+ "name=\"stands-{NUMBER}\"  id=\"stands-{NUMBER}\" onchange=\"handleStopChange(this)\" checked=\"\"></th><th i"
+ "d=\"time\"><input type=\"number\" name=\"duration-{NUMBER}\" value=\"1\"></th><th id=\"delete\"><p class=\"delete pointer\" onclick=\"deleteRoutePart(this)\" id=\"delete-{NUMBER}\">Delete</p></th>"

function addConnection() {
  let input = document.getElementById("newConnection");

  let t = document.getElementById("list");
  let table = t.rows;
  let last = table.length - 3;

  t.insertRow(last);

  let html = template.replaceAll("{NUMBER}", last).replaceAll("{NAME}", input.value);

  input.value = "";

  table[last].innerHTML = html;

  table[last+1].innerHTML = table[last+1].innerHTML
                .replaceAll("\"" + (last) + "\"", "\"" + (last+1) + "\"")
                .replaceAll("-" + (last), "-" + (last+1));

}

function handleStopChange(e) {
  let id = e.id.replaceAll("stands-", "");

  console.log(e.id);
  
  let t = document.getElementById("list");
  let table = t.rows;

  if (!e.checked) {
    table[id].querySelector("#time").innerHTML = "";
  } else {
    table[id].querySelector("#time").innerHTML = "<input type=\"number\" name=\"duration-" + id + "\" value=\"0\">";
  }
}
