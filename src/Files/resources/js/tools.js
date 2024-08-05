class Tools {

    static creatPaginatis(count, show) {
        count = parseInt(count);
        show = parseInt(show);

        var current = show,
            last = count,
            delta = 2,
            left = current - delta,
            right = current + delta + 1,
            range = [],
            rangeWithDots = [],
            l;

        for (let i = 1; i <= last; i++) {
            if (i == 1 || i == last || i >= left && i < right) {
                range.push(i);
            }
        }

        for (let i of range) {
            if (l) {
                if (i - l === 2) {
                    rangeWithDots.push(l + 1);
                } else if (i - l !== 1) {
                    rangeWithDots.push('...');
                }
            }
            rangeWithDots.push(i);
            l = i;
        }

        return rangeWithDots;
    }


    static utils(type,withAll,lang='fa') {
        let all=[];
        if(withAll){
            all.push(['همه',''])
        }
        if(window.utils[type]){

            var items=window.utils[type]
            if(window.utils[type][lang])
                items=window.utils[type][lang]

            for(let item in items){
                all.push([items[item],item])
            }
        }
        return all;
    }

    //========================================================================================================================

    static generateOpts(array,withAll,keyKey,valueKey) {
        let newArray=[];
        if(withAll){
            newArray.push(['همه',''])
        }
        for(let k in array){
            newArray.push([array[k][valueKey],array[k][keyKey]])
        }
        return newArray;
    }


}

export default Tools;
