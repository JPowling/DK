package graph

open class AdjacencyListGraph<E> : AbstractGraph<E>() {

    private val adjacencies: HashMap<Vertex<E>, ArrayList<Edge<E>>> = HashMap()


    override fun createVertex(data: E): Vertex<E> {
        val vertex = Vertex(adjacencies.count(), data)
        adjacencies[vertex] = ArrayList()
        return vertex
    }

    override fun addEdge(source: Vertex<E>, destination: Vertex<E>, weight: Int) {
        val edge = Edge(source, destination, weight)
        adjacencies[source]?.add(edge)
    }

    override fun edges(source: Vertex<E>): ArrayList<Edge<E>> = adjacencies[source] ?: arrayListOf()

    override fun vertesies(): ArrayList<Vertex<E>> = ArrayList(adjacencies.keys)


    override fun weight(source: Vertex<E>, destination: Vertex<E>): Int? {
        return edges(source).firstOrNull() { it.destination == destination }?.weight
    }

    override fun toString(): String {
        return buildString {
            adjacencies.forEach { (vertex, edges) ->
                val edgeString = edges.joinToString { it.destination.data.toString() }
                append("${vertex.data} ---> [ $edgeString ]\n")
            }
        }
    }


}