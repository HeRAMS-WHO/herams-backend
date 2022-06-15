<script lang="ts">
  
  interface Dictionary<T> {
    [Key: string]: T;
  }
  import "./main.css";
  import { onMount } from 'svelte';
  // import { colorMap } from './stores'; 

  import ColorMap from './lib/ColorMap.svelte'
  import { isLoading } from 'svelte-i18n'
  
  import {getContext, setContext} from 'svelte';


  import './i18n';
  import VariablePicker from './lib/VariablePicker.svelte'
  import InputGroup from './lib/InputGroup.svelte';
  import { Form, Field, Select, ErrorMessage, createForm, key } from "svelte-forms-lib";

  import * as yup from "yup";

  export let onSubmit = values => {};
  export let onChange = values => {};

  export let variables: Promise<any>;


  export let dataSortOptions: Dictionary<string> = {

  };
  export let chartTypes = {
    "bar": "Bar chart",
    "donut": "Donut chart"
  };

  export let initialValues = {
    width: 1,
    height: 5,
    sort: 1,
    dataSort: Object.keys(dataSortOptions)[0],
    title: "",
    type: "bar",
    variables: [],
    colorMap: {},
    groupingVariable: ""
  };
  
  let validationSchema;
  try {
    validationSchema = yup.object().noUnknown(false).shape({
      "title": yup.string().required(),
      "sort": yup.number().required().integer(),
      "dataSort": yup.string().required().oneOf(Object.keys(dataSortOptions)),
      "width": yup.number().required().min(1).max(5).integer(),
      "height": yup.number().required().min(1).max(5).required().integer(),
      "type": yup.string().required(),
      // "variables": yup.array().required().min(1),
      "colorMap": yup.object(),
      "groupingVariable": yup.string()
    });
  } catch(e) {
    console.error("Failed to initialize schema", e);
  }

  const formProperties = {
    initialValues,
    onSubmit,
    validationSchema
  }

  const formContext = createForm(formProperties);
  const errors = formContext.errors;
  const form = formContext.form;
  const isValid = formContext.isValid;
  let resolvedVariables: Array<any> = [];

  setContext(key, formContext);

  
  onMount(async () => {
    console.log(chartTypes);
    const result = await variables;
    resolvedVariables = result;
    
  });
  let valueOptions = [];

  $:applicationIsReady = !$isLoading && resolvedVariables.length > 0;

  $: {
      onChange($form);
  }
</script>
<svelte:options tag={null} accessors/>
<main class="mx-auto max-w-xl">
  {#if !applicationIsReady }
    Please wait...
  {:else}
    <form on:submit={formContext.handleSubmit}  >
      <InputGroup label="Element title" field="title" let:field>
        <input name={field} type=text bind:value={$form[field]} on:change={formContext.handleChange}/>
      </InputGroup>
      <InputGroup label="Element width" field="width" let:field>
        <input name={field} type="number"  bind:value={$form[field]} on:change={formContext.handleChange} />
      </InputGroup>
      <InputGroup label="Element height" field="height" let:field>
        <input name={field} type="number" bind:value={$form[field]} on:change={formContext.handleChange} />
      </InputGroup>
      <InputGroup label="Element position" field="sort" let:field>
        <input name={field} type="number"  bind:value={$form[field]} on:change={formContext.handleChange} />
      </InputGroup>
      <InputGroup label="Data sorting" field="dataSort" let:field>
        <select name={field} bind:value={$form[field]} on:change={formContext.handleChange}>
          {#each Object.entries(dataSortOptions) as [type, label] }
            <option value="{type}">{label}</option>  
          {/each}
        </select>
      </InputGroup>
      <InputGroup label="Chart type" field="type" let:field>
        <select name={field} bind:value={$form[field]} on:change={formContext.handleChange}>
          {#each Object.entries(chartTypes) as [type, label]}
            <option value="{type}">{label}</option>  
          {/each}
        </select>
      </InputGroup>
      <InputGroup label="Grouping variable" field="type">
        <select name="groupingVariable" bind:value={$form.groupingVariable}>
          <option value="">No grouping</option>
          {#each resolvedVariables as variable}
            {#if variable.valueOptions}
            <option value="{variable.name}">{variable.label}</option>  
            {/if}
          {/each}
        </select>
      </InputGroup>
      <InputGroup label="Variables" field="variables">
        <VariablePicker name="variables" bind:value={$form.variables} variables={resolvedVariables} bind:valueOptions={valueOptions} />  
      </InputGroup>
      <InputGroup label="Colors" field="colorMap">
      <ColorMap bind:map={$form.colorMap} bind:keys={valueOptions}/>
      </InputGroup>
    <button type="submit" class="bg-pink-400 rounded-md p-2">Submit</button>
  </form>
  {/if}
</main>

<style>
</style>
