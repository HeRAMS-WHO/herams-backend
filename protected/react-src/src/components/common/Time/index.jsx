import dayjs from "dayjs"
import { useEffect, useState } from "react"

const Time = ({ time, format='YYYY-dd-mm' }) => {
    const [formattedTime, setFormattedTime] = useState('')
    useEffect(() => {
        if (time){
            setFormattedTime(dayjs(time).format(format))
        }
        if (!time){
            setFormattedTime('')
        }
    }, [time, format])
    return (<>
        {formattedTime}
    </>)
}

export default Time