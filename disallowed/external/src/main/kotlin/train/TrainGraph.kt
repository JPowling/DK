package train

import graph.AdjacencyListGraph

class TrainGraph : AdjacencyListGraph<TrainStop>() {
    val startNode by lazy { trainStops().first { it.lineID == -1 } }
    val endNode by lazy { trainStops().first { it.lineID == -2 } }


    fun addTrainStop(trainStop: TrainStop) = createVertex(trainStop).data

    fun addPath(path: Path): Path {
        addEdge(getVertex(path.a), getVertex(path.b), path.duration)
        return path
    }

    fun weight(path: Path) = weight(getVertex(path.a), getVertex(path.b))

    fun getVertex(trainStop: TrainStop) = vertices.first { it.data == trainStop }

    fun getEdge(path: Path) = edges(getVertex(path.a)).first { it.source.data == path.a }

    fun trainStops(): MutableSet<TrainStop> {
        val trainStops = mutableSetOf<TrainStop>()
        vertices.forEach {
            trainStops += it.data
        }
        return trainStops
    }


}