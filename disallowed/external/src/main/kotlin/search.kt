import algorithm.Search

fun main(args: Array<String>) {
    println(args.contentToString())
    try {
        Search(args[0], args[1], args[2]).search()
    } catch (e: Exception) {
        e.printStackTrace()
    }
}