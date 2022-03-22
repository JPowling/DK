package algorithm

import train.Connection
import train.TrainGraph
import train.TrainStation
import train.TrainStop
import java.time.LocalTime

class Search() {
    val graph = TrainGraph()
    init {
        val trainstop1 = TrainStop(TrainStation("a", "amsel"), LocalTime.of(10,0,0), 100)
        val trainstop2 = TrainStop(TrainStation("b", "busch"), LocalTime.of(10,10,0), 100)
        graph.addTrainStop(trainstop1)
        graph.addTrainStop((trainstop2))

        graph.addConnection(Connection(trainstop1, trainstop2, 10))
    }

    fun search(){
        println(graph)
    }
}