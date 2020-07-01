var HA_Mixin = {
    data() {
        return {
            init: false,
            HA: HA,
            isLoading: false,
            collapsed: false,
            copied: false,
            labelCol: {
                xs: { span: 24 },
                sm: { span: 8 },
            },
            wrapperCol: {
                xs: { span: 24 },
                sm: { span: 12 },
            },
            messageConfig: {
                top: '72px',
                duration: 2,
                placement: 'topRight'
            },
            showMenu: '',
            winWidth: 0,
            winHeight: 0,
            contentHeight: 0
        }
    },
    created(){
        this.winWidth = window.innerWidth;
        this.winHeight = window.innerHeight;
        this.contentHeight = window.innerHeight;
        if(localStorage.ha_collapsed) this.collapsed = localStorage.ha_collapsed == 'true' ? true : false;
        if(this.HA.isFixed){
            document.getElementById("ha-core").style.minHeight = this.winHeight + "px";
        }else {
            document.getElementById("ha-core").style.minHeight = (this.winHeight - 40) + "px";
            this.contentHeight - 40;
        }
    },
    beforeCreate() {
        window.addEventListener('resize', () => {
            this.winWidth = window.innerWidth;
            this.winHeight = window.innerHeight;
            if(this.winHeight < 768){ this.collapsed = false; }
        })
    },
    methods: {
        switchMenu({ item, key, keyPath }){
            location.href = this.HA.menu_url + key;
        },
        onMenuMobile(){
            this.collapsed = false;
        },
        request (url, params, cb, method = 'GET'){
            var that = this;
            if(!that.isLoading){
                that.isLoading = true;
                that.$notification.config(that.messageConfig);

                params['_nonce'] = this.HA.nonce;

                if(method == 'GET'){

                    if(params){
                        var esc = encodeURIComponent;
                        url += '?' + Object.keys(params)
                            .map(k => esc(k) + '=' + esc(params[k]))
                            .join('&');
                    }
                    axios
                        .get(url)
                        .then(response => {
                            that.isLoading = false
                            if(response.data && response.data.data && !response.data.success && response.data.data['message']){
                                that.$notification.error(response.data.data)
                            }
                            if(typeof cb === 'function'){
                                cb(response.data)
                            }
                        });
                }else{
                    axios
                        .post(url, params).then(response => {
                        that.isLoading = false
                        response = response.data ? response.data : ''
                        if(response){
                            if(response.success){
                                if(response.data['message'])
                                    that.$notification.success(response.data)
                            }else{
                                if(response.data['errors']){
                                    response.data.errors.forEach(function(e){
                                        that.$notification.error({message: e})
                                    })
                                }else{
                                    if(response.data && response.data['message']){
                                        that.$notification.error(response.data)
                                    }
                                }
                            }
                        }
                        if(typeof cb === 'function'){
                            cb(response)
                        }
                    });
                }
            }
        },
        convert(str){
            str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/, 'a');
            str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/, 'e');
            str = str.replace(/(ì|í|ị|ỉ|ĩ)/, 'i');
            str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/, 'o');
            str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/, 'u');
            str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/, 'y');
            str = str.replace(/(đ)/, 'd');
            str = str.replace(/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/, 'A');
            str = str.replace(/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/, 'E');
            str = str.replace(/(Ì|Í|Ị|Ỉ|Ĩ)/, 'I');
            str = str.replace(/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/, 'O');
            str = str.replace(/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/, 'U');
            str = str.replace(/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/, 'Y');
            str = str.replace(/(Đ)/, 'D');
            str = str.replace(/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/, '_');
            str = str.replace(/( )/, '_');
            return str.toLowerCase();
        },
        copy(id){
            var node = document.getElementById( id );
            if ( document.selection ) {
                var range = document.body.createTextRange();
                range.moveToElementText( node  );
                range.select();
            } else if ( window.getSelection ) {
                var range = document.createRange();
                range.selectNodeContents( node );
                window.getSelection().removeAllRanges();
                window.getSelection().addRange( range );
            }
            document.execCommand("copy");
            this.$message.success('Copied', 1);
        },
        number_format(str){
            return str.toFixed(0).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        },
        get_object_val(k, obj, val){
            let item = obj.filter(function(e) { return e.id === k; });
            if(item.length)
                return item[0][val];
            return '';
        }
    },
    mounted(){
        this.init = true;
        this.currentPage = this.HA.currentPage;
        this.currentOpen = this.HA.currentOpen;
        if(this.winHeight < 768){ this.collapsed = false; }
    }
}