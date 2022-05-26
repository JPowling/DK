package graph

/**
 * @E is the Type of the Graph
 * the weight of an Edge is ad Integer
 * @graph is the Graph, where the Algorithm should find the shortest Path between @startNode and @endNode
 * @startNode is the start node
 * @endNode is the end node
 * @startNode and @endNode must be present in @graph
 */
class Dijkstra<E>(val graph: Graph<E>, val startNode: Vertex<E>, val endNode: Vertex<E>) {
    /**
     * stores the current distance to @E, following the shortest path
     */
    var dist = mutableMapOf<Vertex<E>, Int>()

    /**
     * stores for each @E the previous @E, following the shortest path
     */
    var prev = mutableMapOf<Vertex<E>, Vertex<E>>()

    /**
     * Stores every vertex, which is not visited.
     */
    var unvisited = mutableSetOf<Vertex<E>>()


    init {
        for (it in graph.vertices) {
            dist[it] = Int.MAX_VALUE
            unvisited += it
            prev[it] = it
        }
        dist[startNode] = 0
    }

    /**
     * The actual Dijkstra Algorithm, strictly followed by the Wikipedia Pseudocode.
     */
    fun run() {
        while (unvisited.isNotEmpty()) {
            val u = dist.filter { it.key in unvisited }.minByOrNull { (_, v) -> v }!!.key
            unvisited = unvisited.minus(u) as MutableSet<Vertex<E>>

            graph.edges(u).filter { it.destination in unvisited }.forEach {
                val alt = dist[u]!!.plus(it.weight)
                if (alt < 0) {
                    return
                }
                if (alt < dist[it.destination]!!) {
                    dist.replace(it.destination, alt)
                    prev.replace(it.destination, u)
                }
            }
        }
    }

    /**
     * This method finds the shortest path based on the Map "prev", which was generated in run()
     */
    fun interpret(): List<Vertex<E>> {
        val retList = mutableListOf<Vertex<E>>()
        var node = endNode
        var counter = 0
        retList += endNode
        while (counter < 5000 && node != startNode) {
            retList += prev[node]!!
            node = prev[node]!!
            counter++
        }
        if (counter > 4999) {
            return mutableListOf()
        }
        return retList.reversed()
    }

}