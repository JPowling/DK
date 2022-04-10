package algorithm

import org.json.JSONArray
import java.io.File

abstract class AlgorithmHandler (val path: String, val filename: String) {
    var json = getJsonArray()

    abstract fun build()

    private fun getJsonArray() = JSONArray(File(path  + filename).readText(Charsets.UTF_8))
}