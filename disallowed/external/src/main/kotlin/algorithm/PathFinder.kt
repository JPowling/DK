package algorithm

import com.google.gson.Gson
import graph.Dijkstra
import path.PathGraph
import java.io.File

class PathFinder (val path: String, fileName: String, private val uuid: String) {
    val graph = PathGraph()

    init {
        PathGraphHandler(graph, path, fileName).build()
    }

    fun findPath() {
        val file = File("${path}kotlin-${uuid}.json")

        val dijkstra = Dijkstra(graph, graph.getVertex(graph.startNode), graph.getVertex(graph.endNode))

        dijkstra.run()
        val route = dijkstra.interpret()

        file.createNewFile()
        file.writeText(Gson().toJson(route))
        println(Gson().toJson(route))
    }
}