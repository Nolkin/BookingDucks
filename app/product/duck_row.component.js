// component that renders a single product
window.DuckRow = React.createClass({
    //for function removeDuck visibility
    componentDidMount: function(){
        this.removeDuck=this.props.removeDuck;
    },
    render: function() {
        
    return (
        <tr>
            <td>{this.props.item.color}</td>
            <td>{this.props.item.num} </td>
            <td><a href='#'
                    onClick={() => this.removeDuck(this.props.item.color)}
                    className='btn btn-danger'>Del Duck
                </a>
             </td>
        </tr>
        );
    }
});