package graph

/**
 * @E is the Type of the Graph
 * the weight of an Edge is ad Integer
 * @graph is the Graph, where the Algorithm should find the shortest Path between @startNode and @endNode
 * @startNode is the start node
 * @endNode is the end node
 * @startNode and @endNode must be present in @graph
 */
class Dijkstra<E> (val graph: Graph<E>, val startNode: Vertex<E>, val endNode: Vertex<E>){
    /**
     * stores the current distance to @E, following the shortest path
     */
    var dist = mutableMapOf<Vertex<E>, Int>()

    /**
     * stores for each @E the previous @E, following the shortest path
     */
    var prev = mutableMapOf<E, E>()

    /**
     * stores every vertex, which is not visited
     */
    var unvisited = mutableSetOf<Vertex<E>>()


    init {
        for (it in graph.vertices) {
            dist[it] = Int.MAX_VALUE
            unvisited += it
        }
        dist[startNode] = 0
    }


    fun run() {
        while (unvisited.isNotEmpty()) {
            val u = dist.filter { it.key in unvisited }.minByOrNull { (_, v) -> v }!!.key
            unvisited.minus(u)

            graph.edges(u).filter { it.destination in unvisited }.forEach {
                var alt = dist[u]
            }
        }
    }
}