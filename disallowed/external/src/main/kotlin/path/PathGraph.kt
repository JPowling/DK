package path

import graph.AdjacencyListGraph

class PathGraph : AdjacencyListGraph<String>() {
    lateinit var startNode: String
    lateinit var endNode: String

    fun addStation(station: String) {
        if (!stations().contains(station)) {
            createVertex(station).data
        }
    }

    fun addPath(path: Path): Path {
        addEdge(getVertex(path.stationA), getVertex(path.stationB), path.duration)
        return path
    }

    fun getVertex(station: String) = vertices.first { it.data == station }

    fun stations(): MutableSet<String> {
        val stations = mutableSetOf<String>()
        vertices.forEach {
            stations += it.data
        }
        return stations
    }

}