package train

import java.time.LocalTime

data class TrainStop(
    val trainstaion: TrainStation,
    val arrivalTime: LocalTime,
    val lineID: Int
) {

}