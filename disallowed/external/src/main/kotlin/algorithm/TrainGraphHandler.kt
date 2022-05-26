package algorithm

import graph.Vertex
import org.json.JSONObject
import train.*
import java.time.LocalTime

class TrainGraphHandler(private val graph: TrainGraph, path: String, filename: String): AlgorithmHandler(path, filename) {


    private val trainStations: MutableSet<TrainStation> = mutableSetOf()

    init {
        initTrainStationSet()
    }

    override fun build() {
        buildTrainStops()
        buildPathsBetweenLines()
        buildChangePaths()
        var size = 0
        graph.vertices.forEach {size += graph.edges(it).size}
        println(size)
    }

    /**
     * This Method adds every TrainStop as a knot from the JSON-Array into the graph.
     */
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

    /**
     * This Method adds every edge to the graph, which is between to train stations.
     */
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

    /**
     * This Method adds every edge to the graph, which is between to train stops at one station.
     */
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

    //A companion object is used similarly to the keyword static in java.
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