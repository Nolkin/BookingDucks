// component that contains the logic to edit order
window.EditOrderComponent = React.createClass({
    getInitialState: function() {
    // Get this product fields from the data attributes we set on the
    // #content div, using jQuery
    return {
        id: 0,
        SumPrice: 0,
        Ducks: [],
        CountPrice: 0,
        error: ''
    };
},
 
// on mount, read product data and them as this component's state
componentDidMount: function(){
    var orderId = this.props.orderId;
    this.serverRequestProd = $.get("http://test1.ru/test2/api/orders/read_order.php?id=" + orderId,
        function (product) {
            this.setState({CountPrice: product.CountPrice});
            this.setState({id: product.id});
            this.setState({SumPrice: product.SumPrice});
            this.setState({Ducks: product.Ducks});
         }.bind(this));
 
    $('.page-header h1').text('Edit Order');
},

shouldComponentUpdate(){
   console.log("shouldComponentUpdate()");
   return true;
},
componentWillUpdate(){
   console.log("componentWillUpdate()");

},
componentDidUpdate(){
   console.log("componentDidUpdate()");
},

//function, that used for AddDuck button 
addDuck:  function(){
    var orderId = this.props.orderId;
    this.serverRequestDuck = $.get("http://test1.ru/test2/api/orders/add_duck.php?id="+orderId+"&color="+this.state.selectedColor,// + orderId,
        function (product) {
            if (product.message=="Ok")
            {
                this.componentDidMount();
                this.setState({error: ''});
            }
            else this.setState({error: product.message});
        }.bind(this));
   
},

//function, that used for removeDuck button 
removeDuck:  function(clr){
    var orderId = this.props.orderId;
    this.serverRequestDuck = $.get("http://test1.ru/test2/api/orders/remove_duck.php?id="+orderId+"&color="+clr,
        function (product) {
            if (product.message=="Ok")
            {
                this.componentDidMount();
                this.setState({error: ''});
            }
            else this.setState({error: product.message});
         }.bind(this));
},
 
// on unmount, kill order fetching in case the request is still pending
componentWillUnmount: function() {
    this.serverRequestProd.abort();
},

// handle color change
onColorChange: function(e){
    this.setState({selectedColor: e.target.value});
},

 
render: function() {
        //using DuckRow component
        var rows = this.state.Ducks
        .map(function(item, i) {
            return (
                <DuckRow
                    key={i}
                    item={item}
                    removeDuck={this.removeDuck}
                    changeAppMode={this.props.changeAppMode} />
            );
        }.bind(this));
    return (
        <div>
        
            {
                this.state.error == "Duck does not match color." ?
                    <div className='alert alert-danger'>
                        Duck does not match color.
                    </div>
                : null
            }
            {
                this.state.error == "Duck does not exist." ?
                    <div className='alert alert-danger'>
                        Duck does not exist.
                    </div>
                : null
            }
            {
                this.state.error == "Ok" ?
                    <div className='alert alert-success'>
                        Duck added succesfully.
                    </div>
                : null
            }
            
            <div className="form-row">
                 <div className="form-group col-md-2">
                 <a href='#'
                    onClick={() => this.addDuck()}
                    className='btn btn-primary margin-bottom-1em'>
                    Add Duck
                </a>
                </div>
            <div className="form-group col-md-6">
              <select
                                onChange={this.onColorChange}
                                className='form-control'
                                value={this.state.selectedColor}>
                                <option value="-1">Select duck color...</option>
                                <option key="black" value="black">black</option>
                                <option key="green" value="green">green</option>
                                <option key="white" value="white">white</option>
                                <option key="red" value="red">red</option>
                                
             </select>
            </div>
          </div>
            
            <form >
                <table className='table table-bordered table-hover'>
                    <tbody>
                    <tr>
                        <td>id</td>
                        <td colSpan="2">{this.state.id.toString()}</td>
                    </tr>
                    <tr>
                        <td>CountPrice</td>
                        <td colSpan="2">{parseFloat(this.state.CountPrice).toFixed(2)}</td>
                    </tr>
 
                    <tr>
                        <td>SumPrice</td>
                        <td colSpan="2">{parseFloat(this.state.SumPrice).toFixed(2)}</td>
                    </tr>
                  {
                        this.state.Ducks.length != 0  ?
                            <tr>
                              <td colSpan="3"><b>Ducks into Order</b></td>
                            </tr>
                        : null
                    }
            
                    {rows}
 
                    </tbody>
                </table>
            </form>
        </div>
    );
}
});