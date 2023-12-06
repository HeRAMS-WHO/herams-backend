import { signal } from "@preact/signals-react"

const location = signal(window.location.pathname);

export default location;