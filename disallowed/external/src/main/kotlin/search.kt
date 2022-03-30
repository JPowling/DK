import algorithm.Search
import train.TrainGraph
import train.TrainStation
import train.TrainStop
import train.TrainStopType
import java.time.LocalTime

fun main(args: Array<String>) {


//    val graph = TrainGraph()
//
//    val trainStop = TrainStop(TrainStation("station1"), LocalTime.parse("16:30:00"),100, TrainStopType.ARRIVING)
//    graph.addTrainStop(trainStop)
//
//    println(trainStop)
//    println(graph)
//    println(graph.vertices.toString())
//
    Search(args[0], args[1], args[2]).search()
}