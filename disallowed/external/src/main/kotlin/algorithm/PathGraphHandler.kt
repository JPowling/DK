package algorithm

import org.json.JSONArray
import org.json.JSONObject
import path.Path
import path.PathGraph

class PathGraphHandler(private val graph: PathGraph, path: String, filename: String) :
    AlgorithmHandler(path, filename) {

    override fun build() {
        addStartNodes()
        addStations()
        addConnections()
    }

    private fun addStartNodes() {
        graph.startNode = (json[0] as JSONArray)[0] as String
        graph.endNode = (json[0] as JSONArray)[1] as String
    }

    private fun addStations() {
        (json[1] as JSONArray).forEach { it as JSONObject
            graph.addStation(it["BahnhofA"] as String)
            graph.addStation(it["BahnhofB"] as String)
        }
    }

    private fun addConnections() {
        (json[1] as JSONArray).forEach { it as JSONObject
            graph.addPath(Path((it["BahnhofA"] as String), (it["BahnhofB"] as String), (it["Dauer"] as Int)))
        }
    }

}
