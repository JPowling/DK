package algorithm

import org.json.JSONArray
import java.io.File

abstract class AlgorithmHandler (val path: String) {
    protected var json: JSONArray = getJsonArray()

    abstract fun build()

    protected fun getJsonArray() = JSONArray(File(path).readText(Charsets.UTF_8))
}