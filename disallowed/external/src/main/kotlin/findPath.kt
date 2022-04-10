import algorithm.PathFinder
import algorithm.Search

fun main(args: Array<String>) {
//    println("ich bin der PathFinder: ${args.contentToString()}")
    PathFinder(args[0], args[1], args[2]).findPath()
}