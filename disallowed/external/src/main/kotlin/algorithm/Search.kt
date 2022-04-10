package algorithm

import com.google.gson.Gson
import graph.Dijkstra
import train.*
import java.io.File

class Search(private val path: String, fileName: String, private val uuid: String) {
    private val graph = TrainGraph()

    init {
        TrainGraphHandler(graph, path).build()
    }

    fun search() {
        val file = File("${path}kotlin-${uuid}.json")

        val dijkstra = Dijkstra(graph, graph.getVertex(graph.startNode), graph.getVertex(graph.endNode))

        dijkstra.run()
        val route = dijkstra.interpret()

        file.createNewFile()
        file.writeText(Gson().toJson(TrainGraphHandler.getCompact(route)))
    }
}