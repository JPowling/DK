package train

import java.time.LocalTime
import java.time.format.DateTimeFormatter

data class TrainStop(
    val trainStation: TrainStation,
    @Transient val arrivalTime: LocalTime,
    val lineID: Int,
    val stopType: TrainStopType,
) {
    /**
     * This attribute is only for the serialisation to a JSONString since GSON has some problems with LocalTime objects.
     */
    val time: String = arrivalTime.format(DateTimeFormatter.ofPattern("HH:mm"))
}

enum class TrainStopType {
    ARRIVING, DEPARTING,
}