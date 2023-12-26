const dateTime = {
    install(app, options) {
        app.config.globalProperties.$st = (date) => { // this convert utc to system timezone
            if(date !== undefined && date != null){
                date = date.replace(' ', 'T').replace('.000000Z', '') + 'Z';
                return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone})).toLocaleString();
            }
            return ''
        }
    }
}

export default dateTime
