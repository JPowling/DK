package algorithm

import train.*
import java.time.LocalTime

class Search() {
    private val graph = TrainGraph()

    init {
        val trainstop1 = TrainStop(TrainStation("a", "amsel"), LocalTime.of(10, 0), 100, TrainStopType.DEPARTING)
        val trainstop2 = TrainStop(TrainStation("b", "busch"), LocalTime.of(11, 10), 100, TrainStopType.ARRIVING)
        graph.addTrainStop(trainstop1)
        graph.addTrainStop((trainstop2))

        graph.addPath(Path(trainstop1, trainstop2))
        println(Path(trainstop1, trainstop2).duration)
        println(
            graph.getEdge(
                Path(
                    graph.getVertex(
                        TrainStop(
                            TrainStation("a", "amsel"), LocalTime.of(10, 0), 100, TrainStopType.DEPARTING
                        )
                    ).data,
                    graph.getVertex(
                        TrainStop(
                            TrainStation("b", "busch"), LocalTime.of(11, 10), 100, TrainStopType.ARRIVING
                        )
                    ).data
                )
            ).source.data == trainstop1
        )
    }

    fun search() {
        println(graph)
    }
}