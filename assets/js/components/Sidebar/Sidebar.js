import React, { Component } from 'react'

class Sidebar extends Component {
    constructor(props) {
        super(props)

        this.state = {
            menuShown: true
        }
    }

    toggleMenu = () => {
        this.setState({ menuShown: ! this.state.menuShown })
    }

    render() {
        return (
            <div className="c-sidebar__container">
                {this.state.menuShown ? (
                    <nav className={this.state.menuShown ? 'c-sidebar' : 'c-sidebar is-hidden'}>
                        <div className="c-sidebar__header">
                            <span>Menu</span>
                            <span className="c-sidebar__close" onClick={this.toggleMenu}><i className="fas fa-times"></i></span>
                        </div>
                        <div className="c-sidebar__row">
                            <div className="c-sidebar__row-header">
                                <span>Activity</span>
                            </div>
                            <div className="c-sidebar__row-content">
                                <ul>
                                    {this.props.activity.map(activity => {
                                        return <li key={activity.id}><b>{activity.user}</b> {activity.content}</li>
                                    })}
                                </ul>
                            </div>
                        </div>
                    </nav>
                ) : (
                    <div className="c-sidebar__open" onClick={this.toggleMenu}><span>Open Menu</span></div>
                )}
            </div>
        )
    }
}

export default Sidebar