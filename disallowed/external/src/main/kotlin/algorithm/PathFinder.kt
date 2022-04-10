package algorithm

import path.PathGraph
import java.io.File

class PathFinder (val path: String, fileName: String, private val uuid: String) {
    val graph = PathGraph()

    init {
        PathGraphHandler(graph, path).build()
    }

    fun findPath() {
        val file = File("${path}kotlin-${uuid}.json")
    }
}