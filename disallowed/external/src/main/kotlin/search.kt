import algorithm.Search
import kotlin.time.ExperimentalTime
import kotlin.time.measureTime

@OptIn(ExperimentalTime::class)
fun main(args: Array<String>) {
//    println(args.contentToString())
    println( measureTime {
        Search(args[0], args[1], args[2]).search()
    })
}