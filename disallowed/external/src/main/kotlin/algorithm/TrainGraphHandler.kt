package algorithm

import graph.Vertex
import org.json.JSONObject
import train.*
import java.time.LocalTime

class TrainGraphHandler(private val graph: TrainGraph, path: String): AlgorithmHandler(path) {


    private val trainStations: MutableSet<TrainStation> = mutableSetOf()

    init {
        json = getJsonArray()
        initTrainStationSet()
    }

    override fun build() {
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

    private fun initTrainStationSet() {
        json.forEach {
            if (it is JSONObject) trainStations += TrainStation(it.get("Name") as String)
        }
    }

    companion object {
        fun getCompact(route: List<Vertex<TrainStop>>): List<Vertex<TrainStop>> {
            val retList = mutableListOf<Vertex<TrainStop>>()
            route.forEachIndexed { i, it ->
                if (i + 1 < route.size && it.data.lineID != route[i + 1].data.lineID) {
                    retList += it
                    retList += route[i + 1]
                }
            }
            return retList
        }
    }
}