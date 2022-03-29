package train

import java.time.LocalTime

class Path(
    val a: TrainStop,
    val b: TrainStop,
) {
    val duration: Int by lazy {
        b.arrivalTime - a.arrivalTime
    }

    private operator fun LocalTime.minus(timeA: LocalTime): Int {
        val hours = b.arrivalTime.hour - timeA.hour
        val minutes = b.arrivalTime.minute - timeA.minute
        return hours * 60 + minutes
    }
}


