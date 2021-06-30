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

    static utils(type,withAll) {
        let a=[];
        if(withAll){
            a.push(['همه',''])
        }
        if(window.utils[type]){
            for(let k in window.utils[type]){
                a.push([window.utils[type][k],k])
            }
        }
        return a;
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
