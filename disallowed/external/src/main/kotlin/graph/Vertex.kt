package graph

data class Vertex<E>(
    val index: Int, val data: E
) {
    override fun toString(): String {
        return data.toString()
    }
}