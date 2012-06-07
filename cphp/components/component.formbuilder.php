<?php
cphp_dependency_provides("cphp_formbuilder", "1.0");

$cphp_formbuilder_increment = 0;

abstract class CPHPFormBuilderBaseClass
{
	public $parameters = array();
	
	public function AddParameter($key, $value)
	{
		$this->parameters[$key] = $value;
	}
	
	public function RenderParameters($parameters)
	{
		if(empty($parameters))
		{
			return "";
		}
		
		$rendered = array();
		
		foreach($parameters as $key => $value)
		{
			$value = utf8entities($value);
			$rendered[] = "{$key}=\"{$value}\"";
		}
		
		return " " . implode(" ", $rendered);
	}
	
	public function RenderNote()
	{
		if(!empty($this->note))
		{
			return "<div class=\"cphp_fbd_note\">{$this->note}</div>";
		}
		else
		{
			return "";
		}
	}
}

abstract class CPHPFormBuilderContainer extends CPHPFormBuilderBaseClass
{
	public $elements = array();
	
	public function AddElement($element)
	{
		$this->elements[] = $element;
	}
}

class CPHPFormBuilder extends CPHPFormBuilderContainer
{
	public $method = "";
	public $action = "";
	
	public function __construct($method, $target)
	{
		$this->method = strtolower($method);
		$this->action = $target;
	}
	
	public function Render()
	{		
		$rendered_elements = "";
		
		foreach($this->elements as $element)
		{
			$rendered_elements .= $element->Render();
		}
		
		$this->AddParameter("method", $this->method);
		$this->AddParameter("action", $this->action);
		
		$rendered_parameters = $this->RenderParameters($this->parameters);
		
		return "<form{$rendered_parameters}>{$rendered_elements}</form>";
	}
}

class CPHPFormSection extends CPHPFormBuilderContainer
{
	public $label = "";
	public $fieldset = true;
	public $classname = "";
	
	public function __construct($fieldset = true, $label = "")
	{
		if(!empty($label))
		{
			$this->label = $label;
		}
		
		$this->fieldset = $fieldset;
	}
	
	public function Render()
	{
		if(!empty($this->label))
		{
			$legend = "<legend>{$this->label}</legend>";
		}
		else
		{
			$legend = "";
		}
		
		if($this->fieldset === true)
		{
			$this->classname = trim("{$this->classname} cphp_fbd_fieldset");
		}
		
		$rendered_elements = "";
		
		foreach($this->elements as $element)
		{
			$rendered_elements .= $element->Render();
		}
		
		if($this->fieldset === true)
		{
			$this->AddParameter("class", $this->classname);
			$rendered_parameters = $this->RenderParameters($this->parameters);
			return "<fieldset{$rendered_parameters}>{$legend}<div class=\"cphp_fbd_form\">{$rendered_elements}</div></fieldset>";
		}
		else
		{
			return "<div class=\"cphp_fbd_form\"{$rendered_parameters}>{$rendered_elements}</div>";
		}
	}
}

abstract class CPHPFormInputElement extends CPHPFormBuilderBaseClass
{
	public $id = "";
	public $name = "";
	public $value = "";
	public $label = "";
	
	public function __construct($label, $name, $value = "", $note = "", $id = "")
	{
		global $cphp_formbuilder_increment;
		
		$this->name = $name;
		$this->value = $value;
		$this->label = $label;
		$this->note = $note;
		
		if(empty($id))
		{
			$this->id = "cphp_fbd_{$cphp_formbuilder_increment}";
			$cphp_formbuilder_increment += 1;
		}
		else
		{
			$this->id = $id;
		}
	}
	
}

abstract class CPHPFormInput extends CPHPFormInputElement
{
	public function Render()
	{
		$this->AddParameter("id", $this->id);
		$this->AddParameter("type", $this->type);
		$this->AddParameter("name", $this->name);
		$this->AddParameter("value", $this->value);
		
		$rendered_parameters = $this->RenderParameters($this->parameters);
		$rendered_note = $this->RenderNote();
		
		return "<div class=\"cphp_fbd_row\"><div class=\"cphp_fbd_label\">{$this->label}{$rendered_note}</div><div class=\"cphp_fbd_field\"><input{$rendered_parameters}></div></div>";
	}
}

class CPHPFormTextInput extends CPHPFormInput
{
	public $type = "text";
}

class CPHPFormPasswordInput extends CPHPFormInput
{
	public $type = "password";
}

class CPHPFormDateInput extends CPHPFormInput
{
	public $type = "date";
}

class CPHPFormTimeInput extends CPHPFormInput
{
	public $type = "time";
}

class CPHPFormEmailInput extends CPHPFormInput
{
	public $type = "email";
}

class CPHPFormUrlInput extends CPHPFormInput
{
	public $type = "url";
}

class CPHPFormRangeInput extends CPHPFormInput
{
	public $type = "range";
}

class CPHPFormColorInput extends CPHPFormInput
{
	public $type = "color";
}

class CPHPFormSearchInput extends CPHPFormInput
{
	public $type = "search";
}

class CPHPFormCheckboxGroup extends CPHPFormBuilderContainer
{
	public function __construct($label, $note = "")
	{
		global $cphp_formbuilder_increment;
		
		$this->label = $label;
		$this->note = $note;
	}
	
	public function Render()
	{		
		$rendered_note = $this->RenderNote();
		
		$rendered_elements = "";
		
		foreach($this->elements as $element)
		{
			$rendered_elements .= $element->Render();
		}
		
		return "<div class=\"cphp_fbd_row\"><div class=\"cphp_fbd_label\">{$this->label}{$rendered_note}</div><div class=\"cphp_fbd_field\">{$rendered_elements}</div></div>";
	}
}

class CPHPFormCheckbox extends CPHPFormInputElement
{
	
	public function Render()
	{
		$this->AddParameter("id", $this->id);
		$this->AddParameter("type", "checkbox");
		$this->AddParameter("name", $this->name);
		
		if($this->value === true)
		{
			$this->AddParameter("checked", "");
		}
		
		$rendered_parameters = $this->RenderParameters($this->parameters);
		$rendered_note = $this->RenderNote();
		return "<div class=\"cphp_fbd_cblabel\"><input{$rendered_parameters}><label for=\"{$this->id}\">{$this->label}{$rendered_note}</label></div>";
	}
}

class CPHPFormRadioButton extends CPHPFormInput
{
	public $type = "radio";
}

class CPHPFormButton extends CPHPFormInputElement
{
	public $type = "button";
	
	public function Render()
	{
		$this->AddParameter("type", $this->type);
		$this->AddParameter("name", $this->name);
		$this->AddParameter("value", $this->value);
		
		$rendered_parameters = $this->RenderParameters($this->parameters);
		
		return "<div class=\"cphp_fbd_row\"><div class=\"cphp_fbd_label\"></div><div class=\"cphp_fbd_field\"><button{$rendered_parameters}>{$this->label}</button></div></div>";
	}
}

class CPHPFormSubmitButton extends CPHPFormButton
{
	public $type = "submit";
}

class CPHPFormResetButton extends CPHPFormButton
{
	public $type = "reset";
}

class CPHPFormSelect extends CPHPFormInputElement
{
	public $options = array();
	
	public function AddOption($option)
	{
		$this->options[] = $option;
	}
	
	public function Render()
	{
		$this->AddParameter("id", $this->id);
		$this->AddParameter("name", $this->name);
		
		$rendered_parameters = $this->RenderParameters($this->parameters);
		$rendered_note = $this->RenderNote();
		
		$list = "";
		
		foreach($this->options as $option)
		{
			$list .= $option->Render();
		}
		
		return "<div class=\"cphp_fbd_row\"><div class=\"cphp_fbd_label\">{$this->label}{$rendered_note}</div><div class=\"cphp_fbd_field\"><select{$rendered_parameters}>{$list}</select></div></div>";
	}
}

class CPHPFormSelectOptionGroup
{
	public $label = "";
	public $options = array();
	
	public function __construct($label)
	{
		$this->label = $label;
	}
	
	public function AddOption($option)
	{
		$this->options[] = $option;
	}
	
	public function Render()
	{
		$list = "";
		
		foreach($this->options as $option)
		{
			$list .= $option->Render();
		}
		
		return "<optgroup label=\"{$this->label}\">{$list}</optgroup>";
	}
}

class CPHPFormSelectOption
{
	public $value = "";
	public $description = "";
	public $selected = false;
	
	public function __construct($value, $description, $selected = false)
	{
		$this->value = $value;
		$this->description = $description;
		$this->selected = $selected;
	}
	
	public function Render()
	{
		$selected_attribute = ($selected) ? " selected" : "";
		
		return "<option value=\"{$this->value}\"{$selected_attribute}>{$this->description}</option>";
	}
}
?>
