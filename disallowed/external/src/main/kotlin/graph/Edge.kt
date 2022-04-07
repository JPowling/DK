package graph

data class Edge<E>(
    val source: Vertex<E>,
    val destination: Vertex<E>,
    val weight: Int,
)