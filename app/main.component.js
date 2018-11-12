 // component that decides which main component to load: read or create/update
    var MainApp = React.createClass({
     
        // initial mode is 'read' mode
        getInitialState: function(){
            return {
                currentMode: 'newOrder',
                orderId: null
            };
        },
     
        // used when use clicks something that changes the current mode
        changeAppMode: function(newMode, orderId){
                 
            if(orderId !== undefined){
                this.setState({orderId: orderId});
            }
             this.setState({currentMode: newMode});
            
        },
     
        // render the component based on current or selected mode
        render: function(){
     
            var modeComponent =
                <NewOrderComponent
                changeAppMode={this.changeAppMode} />;
     
            switch(this.state.currentMode){
                case 'newOrder':
                    break;
                case 'editOrder':
                    modeComponent = <EditOrderComponent orderId={this.state.orderId} changeAppMode={this.changeAppMode}/>;
                    break;  
                default:
                    break;
            }
     
            return modeComponent;
        }
    });
     
    // go and render the whole React component on to the div with id 'content'
    ReactDOM.render(
        <MainApp />,
        document.getElementById('content')
    );