/**
 * Blocks Helper Module
 *
 * This module is responsible for importing and registering the IconSelectControl component
 * to the global lhbasics.components namespace.
 */

// Import components
import IconSelectControl from './components/icon-select-control';
import LHIcon from './components/icon';
import MediaSelectControl from './components/media-select-control';
import EntitySelectControl, {
	PostSelectControl,
	TaxonomySelectControl,
} from './components/entity-select-control';
import WeblinkControl from './components/weblink-control';
import WeblinkSetting from './components/weblink-control/setting';
import WeblinkToolbarButton from './components/weblink-control/toolbar-button';

window.lhbasics = window.lhbasics || {};
window.lhbasics.components = window.lhbasics.components || {};

// Register the IconSelectControl component to the global lhbasics.components namespace
window.lhbasics.components.IconSelectControl = IconSelectControl;

// Register the LHIcon component to the global lhbasics.components namespace
window.lhbasics.components.LHIcon = LHIcon;

// Register the EntitySelectControl component to the global lhbasics.components namespace
window.lhbasics.components.EntitySelectControl = EntitySelectControl;
window.lhbasics.components.PostSelectControl = PostSelectControl;
window.lhbasics.components.TaxonomySelectControl = TaxonomySelectControl;

// Register the MediaSelectControl component to the global lhbasics.components namespace
window.lhbasics.components.MediaSelectControl = MediaSelectControl;

// Register the WeblinkControl component to the global lhbasics.components namespace
window.lhbasics.components.WeblinkControl = WeblinkControl;
window.lhbasics.components.WeblinkSetting = WeblinkSetting;
window.lhbasics.components.WeblinkToolbarButton = WeblinkToolbarButton;
