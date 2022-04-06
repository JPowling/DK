package algorithm

import org.json.JSONArray
import org.json.JSONObject
import train.*
import java.io.File
import java.io.FileNotFoundException
import java.time.LocalTime

class TrainGraphBuilder(val graph: TrainGraph, val path: String, val fileName: String) {

    private var json: JSONArray
    private val trainStations: MutableSet<TrainStation> = mutableSetOf()

    init {
        json = loadToJSONArray()
        initTrainStationSet()
    }

    fun build() {
        buildTrainStops()
        buildPathsBetweenLines()
        buildChangePaths()
    }

    private fun buildTrainStops() {
        json.forEach {
            if (it is JSONObject) {
                graph.addTrainStop(TrainStop(
                    TrainStation(it.get("Name") as String),
                    LocalTime.parse(it.get("StopTime") as String),
                    it.get("LinienID") as Int,
                    TrainStopType.valueOf(it.get("StopType") as String)
                ))
            }
        }
    }

    private fun buildPathsBetweenLines() {
        json.forEachIndexed { i, it ->
            if (it is JSONObject && it.get("StopType") == "DEPARTING") {
//                println("${json.length()}, $i")
                if (json.length() > i + 1 && json[i + 1] is JSONObject) {
                    graph.addPath(Path(
                        TrainStop(
                            TrainStation(it.get("Name") as String),
                            LocalTime.parse(it.get("StopTime") as String),
                            it.get("LinienID") as Int,
                            TrainStopType.DEPARTING
                        ),
                        TrainStop(
                            TrainStation((json[i + 1] as JSONObject).get("Name") as String),
                            LocalTime.parse((json[i + 1] as JSONObject).get("StopTime") as String),
                            (json[i + 1] as JSONObject).get("LinienID") as Int,
                            TrainStopType.ARRIVING
                        )
                    ))
                }
            }
        }
    }

    private fun buildChangePaths() {
        trainStations.forEach { trainStation ->
            val trainStops =
                graph.trainStops()
                    .filter { it.trainStation == trainStation }
                    .sortedBy { it.arrivalTime }

            val trainStopArr = trainStops.filter { it.stopType == TrainStopType.ARRIVING }
            val trainStopDep = trainStops.filter { it.stopType == TrainStopType.DEPARTING }

            trainStopArr.forEach { trainStop ->
                trainStopDep
                    .filter { it.arrivalTime >= trainStop.arrivalTime }
                    .forEach { graph.addPath(Path(trainStop, it)) }
            }
        }
    }

    private fun loadToJSONArray(): JSONArray {
        if (!File(path + fileName).exists()) {
            println("das gibt's doch jetzt nicht...")
            throw FileNotFoundException()
        }
        return JSONArray(File(path + fileName).readText(Charsets.UTF_8))
    }

    private fun initTrainStationSet() {
        json.forEach {
            if (it is JSONObject) trainStations += TrainStation(it.get("Name") as String)
        }
    }
}