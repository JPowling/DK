package algorithm

import org.json.JSONArray
import org.json.JSONObject
import train.*
import java.io.File
import java.io.FileNotFoundException
import java.time.LocalTime

class TrainGraphBuilder(val graph: TrainGraph, val path: String, val fileName: String) {

    private var json: JSONArray

    init {
        json = loadToJSONArray()
    }

    fun build() {
        buildTrainStops()
        println(graph)
        buildPathsBetweenLines()
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
        println("--------------------------------")
        json.forEach {
            if (it is JSONObject && it.get("StopType") == "DEPARTING") {
                println(it)
                graph.addPath(Path(
                    TrainStop(
                        TrainStation(it.get("Name") as String),
                        LocalTime.parse(it.get("StopTime") as String),
                        it.get("LinienID") as Int,
                        TrainStopType.DEPARTING
                    ),
                    TrainStop(
                        TrainStation(it.get("NextStop") as String),
                        LocalTime.parse(it.get("NextStopTime") as String),
                        it.get("LinienID") as Int,
                        TrainStopType.ARRIVING
                    )
                ))
            }
        }
        println("---------------------------")
    }

    private fun loadToJSONArray(): JSONArray {
        if (!File(path + fileName).exists()) {
            println("dat gibts doch jz nicht...")
            throw FileNotFoundException()
        }
        return JSONArray(File(path + fileName).readText(Charsets.UTF_8))
    }
}