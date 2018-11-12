// component that contains all the logic and other smaller components
// that form the Read Products view
window.NewOrderComponent = React.createClass({
    getInitialState: function() {
        return {
             owner: '',
             successCreation: null
        };
    },
 
    // on mount, fetch all products and stored them as this component's state
    componentDidMount: function() {
 
    },
 
    // on unmount, kill product fetching in case the request is still pending
    componentWillUnmount: function() {
    },
    
    // handle name change
    onNameChange: function(e) {
        this.setState({name: e.target.value});
    },
    
    // handle save button clicked
    onSave: function(e){
     
        // data in the form
        var form_data={
            owner: this.state.name
            
        };
     
        // submit form data to api
        $.ajax({
            url: "http://test1.ru/test2/api/orders/create.php",
            type : "POST",
            contentType : 'application/json',
            data : JSON.stringify(form_data),
            success : function(response) {
     
                // api message
                this.setState({successCreation: response['message']});
     
                // empty form
               // this.setState({name: ""});
                
                // empty form
                this.setState({orderId: response['id']});
                this.props.changeAppMode('editOrder', response['id']);
     
            }.bind(this),
            error: function(xhr, resp, text){
                // show error to console
                console.log(xhr, resp, text);
                this.setState({successCreation: xhr.responseJSON.message});
                //console.log(xhr.responseJSON.message);
            }.bind(this)
        });
     
        e.preventDefault();
    },
 
    // render component on the page
    render: function() {
        // list of products
                $('.page-header h1').text('New Order');
 
           
      return (
        <div>
            {
     
                this.state.successCreation == "Order was created." ?
                    <div className='alert alert-success'>
                        Order was created.
                    </div>
                : null
            }
     
            {
     
                this.state.successCreation == "Unable to create product." ?
                    <div className='alert alert-danger'>
                        Unable to save product. Please try again.
                    </div>
                : null
            }
            {
     
                this.state.successCreation == "Unable to create product. Data is incomplete." ?
                    <div className='alert alert-danger'>
                        Unable to save product. Data is incomplete. Please, insert all data.
                    </div>
                : null
            }
                
     
            <form onSubmit={this.onSave}>
                <table className='table table-bordered table-hover'>
                <tbody>
                    <tr>
                        <td>Name</td>
                        <td>
                            <input
                            type='text'
                            className='form-control'
                            value={this.state.name}
                            required
                            onChange={this.onNameChange} />
                        </td>
                    </tr>
     
                    
     
                    <tr>
                        <td></td>
                        <td>
                            <button
                            className='btn btn-primary'
                            onClick={this.onSave}>Save</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
        );
    }
});