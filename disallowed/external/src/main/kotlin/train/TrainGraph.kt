package train

import graph.AdjacencyListGraph

class TrainGraph : AdjacencyListGraph<TrainStop>() {

    fun addTrainStop(trainStop: TrainStop) = createVertex(trainStop).data

    fun addPath(path: Path) {
        addEdge(getVertex(path.a), getVertex(path.b), path.duration)
    }

    fun weight(path: Path) = weight(getVertex(path.a), getVertex(path.b))

    fun getVertex(trainStop: TrainStop) = vertices.first { it.data == trainStop }

    fun getEdge(path: Path) = edges(getVertex(path.a)).first { it.source.data == path.a }

}