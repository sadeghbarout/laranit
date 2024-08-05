const urlMixin={
    mounted(){
        const urlSearchParams = new URLSearchParams(window.location.search);
        const urlParams = Object.fromEntries(urlSearchParams.entries());

        // console.log("URL")
        // console.log(urlParams)

        Object.entries(urlParams).map(([key, value]) => {
            var cameCaseKey =  key.replace(/_./g, x=>x[1].toUpperCase())
            if( this[cameCaseKey] !== undefined){
                this[cameCaseKey] = value
            }
        })
    }
};

export default urlMixin