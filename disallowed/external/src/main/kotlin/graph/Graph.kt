package graph

interface Graph<E> {

    fun createVertex(data: E): Vertex<E>

    fun addEdge(
        source: Vertex<E>,
        destination: Vertex<E>,
        weight: Int
    )

    fun edges(source: Vertex<E>): ArrayList<Edge<E>>

    fun vertesies(): ArrayList<Vertex<E>>

    fun weight(
        source: Vertex<E>,
        destination: Vertex<E>
    ): Int?

}