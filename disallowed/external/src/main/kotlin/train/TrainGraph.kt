package train

import graph.AdjacencyListGraph
import graph.Vertex

class TrainGraph : AdjacencyListGraph<TrainStop>() {

    fun addTrainStop(trainStop: TrainStop): TrainStop {
        return createVertex(trainStop).data
    }

    fun addConnection(connection: Connection) {
        getVertex(connection.a)?.let { getVertex(connection.b)?.let { it1 -> addEdge(it, it1, connection.duration) } }
    }

    fun weight(connection: Connection): Int? =
        getVertex(connection.a)?.let { getVertex(connection.b)?.let { it1 -> weight(it, it1) } }

    fun getVertex(trainStop: TrainStop): Vertex<TrainStop>? =
        vertesies().firstOrNull() { it.data == trainStop }

    fun AdjacencyListGraph<TrainStop>.getEdge(connection: Connection) =
        getVertex(connection.a)?.let { edges(it) }?.firstOrNull() { it.source.data == connection.a }

}