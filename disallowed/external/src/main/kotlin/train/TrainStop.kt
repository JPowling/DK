package train

import java.time.LocalTime

data class TrainStop(
    val trainStation: TrainStation,
    val arrivalTime: LocalTime,
    val lineID: Int,
    val stopType: TrainStopType,
)

enum class TrainStopType {
    ARRIVING,
    DEPARTING,

}