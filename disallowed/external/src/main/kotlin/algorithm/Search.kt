package algorithm

import graph.Dijkstra
import graph.Vertex
import train.*
import java.io.File
import java.time.LocalTime

class Search(private val path: String, private val fileName: String, private val uuid: String) {
    private val graph = TrainGraph()

    init {
        TrainGraphBuilder(graph, path, fileName).build()
    }

    fun search() {
        println(graph)
        val file = File("${path}kotlin-${uuid}.json")
//        file.createNewFile()
//        file.writeText(graph.toString())
//        println("created file")


        val dijkstra = Dijkstra(graph,
            //graph.getVertex(graph.startNode),
            graph.getVertex(
                TrainStop(
                    TrainStation("Heilbronn Hbf"),
                    LocalTime.parse("10:00:00"),
                    100,
                    TrainStopType.DEPARTING
                )
            ),
            graph.getVertex(
                TrainStop(TrainStation("Stuttgart Hbf"),
                    LocalTime.parse("21:21:00"),
                    117,
                    TrainStopType.ARRIVING
                )))     //graph.getVertex(graph.endNode))
        dijkstra.run()
        println("the shortest path is: ${dijkstra.interpret()}")

    }
}