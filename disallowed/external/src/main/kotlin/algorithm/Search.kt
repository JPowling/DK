package algorithm

import graph.Dijkstra
import train.*
import java.io.File

class Search(private val path: String, private val fileName: String, private val uuid: String) {
    private val graph = TrainGraph()

    init {
        TrainGraphBuilder(graph, path, fileName).build()
    }

    fun search() {
        println(graph)
        val file = File("${path}kotlin-${uuid}.json")
        file.createNewFile()
        file.writeText(graph.toString())
        println("created file")


        Dijkstra<TrainStop, Int>(graph, graph.startNode, graph.endNode)
    }
}