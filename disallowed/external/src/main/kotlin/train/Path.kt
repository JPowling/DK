package train

import java.time.LocalTime
import java.time.temporal.ChronoUnit

data class Path(
    val a: TrainStop,
    val b: TrainStop,
) {
    val duration by lazy { a.arrivalTime.until(b.arrivalTime, ChronoUnit.MINUTES).toInt() }
}


