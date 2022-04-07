package graph


open class AdjacencyListGraph<E> : AbstractGraph<E>() {

    private val adjacencies = mutableMapOf<Vertex<E>, MutableList<Edge<E>>>()

    override fun createVertex(data: E): Vertex<E> =
        Vertex(adjacencies.count(), data).also { adjacencies[it] = mutableListOf() }

    override fun addEdge(source: Vertex<E>, destination: Vertex<E>, weight: Int) {
        adjacencies[source]!! += Edge(source, destination, weight)
    }

    override fun edges(source: Vertex<E>) = adjacencies[source]!!

    override val vertices = adjacencies.keys

    override fun weight(source: Vertex<E>, destination: Vertex<E>) =
        edges(source).first { it.destination == destination }.weight

    override fun toString(): String {
        return buildString {
            adjacencies.forEach { (vertex, edges) ->
                val edgeString = edges.joinToString { it.destination.data.toString() }
                append("${vertex.data} ---> [ $edgeString ]\n")
            }
        }
    }

}