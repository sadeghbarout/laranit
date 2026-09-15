<!--
  ==========================================================================
  مثال‌های استفاده از کامپوننت form-select2
  ==========================================================================

  مثال ۱: حالت ساده با آپشن‌های استاتیک (چند‌تایی، بدون محدودیت)
  <form-select2
      title="دسته‌بندی"
      :options="[
          {id: 1, name: 'تست 1'},
          {id: 2, name: 'تست 2'},
          {id: 3, name: 'تست 3'},
      ]"
      value-key="id"
      label-key="name"
      v-model="data"
  />

  مثال ۲: آپشن استاتیک + محدود کردن انتخاب به حداکثر ۳ مورد
  می‌توانید v-model را از همان ابتدا با idهای خام پر کنید (مثل [1, 2]):
  کامپوننت خودش آن‌ها را با options موجود تطبیق داده و به آبجکت کامل تبدیل می‌کند
  <form-select2
      title="برچسب‌ها"
      :options="[
          {id: 1, name: 'تست 1'},
          {id: 2, name: 'تست 2'},
          {id: 3, name: 'تست 3'},
      ]"
      value-key="id"
      label-key="name"
      :max="3"
      v-model="data"
  />

  مثال ۳: حالت تک‌انتخابی (multiple=false)
  خروجی v-model در این حالت فقط id خام است (نه آبجکت)، مثلاً: 2
  <form-select2
      title="وضعیت"
      :options="[
          {id: 1, name: 'فعال'},
          {id: 2, name: 'غیرفعال'},
      ]"
      value-key="id"
      label-key="name"
      :multiple="false"
      v-model="statusId"
  />

  مثال ۴: دریافت لیست از API (پاسخ باید به‌صورت { items: [...] } باشد)
  <form-select2
      title="کاربران"
      api-url="/api/users/list"
      value-key="id"
      label-key="name"
      :max="5"
      v-model="data"
  />

  مثال ۵: جستجوی Async — با هر بار تایپ، دوباره به API با پارامتر query
  درخواست زده می‌شود (سرور باید بر اساس ?query=... فیلتر کند)
  <form-select2
      title="محصولات"
      api-url="/api/products/list"
      value-key="id"
      label-key="name"
      async-search
      v-model="data"
  />

  مثال ۶: v-model از قبل (یا با تاخیر) پر از idهای خام است و api-url هم دارید
  کامپوننت خودش برای idهای پیدا‌نشده در options محلی، یک درخواست جدا با
  پارامتر ids می‌زند تا label واقعی‌شان را از سرور بگیرد
  <form-select2
      title="اعضای گروه"
      api-url="/api/members/list"
      value-key="id"
      label-key="name"
      v-model="data"
  />
  data می‌تواند بعداً با تاخیر مقداردهی شود: data.value = [1, 2, 3] -->

<script setup>
import Multiselect from 'vue-multiselect'
import axios from 'axios'
import {onMounted, onBeforeUnmount, ref, computed, watch} from "vue";

const props = defineProps({
    title: {type: String, default: null},
    options: {type: Array, default: () => []},
    apiUrl: {type: String, default: null},
    placeholder: {type: String, default: 'یک یا چند مورد را انتخاب کنید'},
    labelKey: {type: String, default: 'label'},
    valueKey: {type: String, default: 'value'},
    colum: {type: Boolean, default: true},
    max: {type: Number, default: null},
    taggable: {type: Boolean, default: false},
    multiple: {type: Boolean, default: true},
    asyncSearch: {type: Boolean, default: false},
    searchDebounce: {type: Number, default: 800},
})

const model = defineModel({default: null});

const optionsList = ref([]);
const isLoading = ref(false);
let debounceTimer = null;

function findOptionByValue(val) {
    if (val === null || val === undefined) return null;
    return optionsList.value.find(
        (opt) => String(opt?.[props.valueKey]) === String(val)
    ) ?? null;
}

function mergeIntoOptionsList(items) {
    items.forEach((item) => {
        const exists = optionsList.value.some(
            (opt) => String(opt[props.valueKey]) === String(item[props.valueKey])
        );
        if (!exists) optionsList.value.push(item);
    });
}

// سعی می‌کند idهای خام موجود در model را از روی optionsList فعلی
// (چه استاتیک، چه قبلاً از API آمده) به آبجکت کامل تبدیل کند.
// بدون درخواست شبکه است، پس بی‌خطر است که چندبار صدا زده شود.
function resolveFromLocal() {
    if (!props.multiple) return;
    if (!Array.isArray(model.value) || !model.value.length) return;
    if (!optionsList.value.length) return;

    let changed = false;
    const next = model.value.map((item) => {
        if (item && typeof item === 'object') return item;
        const found = findOptionByValue(item);
        if (found) {
            changed = true;
            return found;
        }
        return item;
    });

    if (changed) model.value = next;
}

// تنها زمانی صدا زده می‌شود که بعد از resolveFromLocal هنوز id خام باقی مانده
// و apiUrl داریم؛ مستقیماً همان idها را با پارامتر ids درخواست می‌کند.
async function resolveRawIds(rawIds) {
    if (!props.apiUrl || !rawIds.length) return;

    isLoading.value = true;
    try {
        const {data} = await axios.get(props.apiUrl, {
            params: {ids: rawIds},
        });
        const fetched = data?.items ?? [];
        mergeIntoOptionsList(fetched);

        model.value = model.value.map((item) => {
            if (item && typeof item === 'object') return item;
            return findOptionByValue(item) ?? {
                [props.valueKey]: item,
                [props.labelKey]: item,
                __notFound: true,
            };
        });
    } catch (error) {
        console.error('MultiSelect resolveRawIds error:', error);
        model.value = model.value.map((item) => {
            if (item && typeof item === 'object') return item;
            if (!rawIds.includes(item)) return item;
            return {
                [props.valueKey]: item,
                [props.labelKey]: item,
                __notFound: true,
            };
        });
    } finally {
        isLoading.value = false;
    }
}

const internalValue = computed({
    get() {
        if (props.multiple) {
            const arr = Array.isArray(model.value) ? model.value : [];
            return arr.map((item) => {
                if (item && typeof item === 'object') return item;
                return findOptionByValue(item) ?? {[props.valueKey]: item, [props.labelKey]: String(item)};
            });
        }

        if (model.value && typeof model.value === 'object') return model.value;
        return findOptionByValue(model.value);
    },
    set(value) {
        handleInput(value);
    },
})

function handleInput(value) {
    if (props.multiple) {
        model.value = Array.isArray(value) ? value : [];
    } else {
        model.value = value ? value[props.valueKey] : null;
    }
}

async function fetchOptions(query = '') {
    if (!props.apiUrl) return;

    isLoading.value = true;
    try {
        const {data} = await axios.get(props.apiUrl, {
            params: {
                query: query??'',
            },
        });
        optionsList.value = data?.items ?? [];
    } catch (error) {
        console.error('MultiSelect fetchOptions error:', error);
    } finally {
        isLoading.value = false;
    }
}

function onSearchChange(query) {
    if (!props.asyncSearch || !props.apiUrl) return;

    if (debounceTimer) clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        fetchOptions(query);
    }, props.searchDebounce);
}

function addTag(newTag) {
    const tag = {
        [props.labelKey]: newTag,
        [props.valueKey]: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000)),
    }
    optionsList.value.push(tag)

    if (props.multiple) {
        internalValue.value = [...(internalValue.value ?? []), tag]
    } else {
        internalValue.value = tag
    }
}

// هر بار model از بیرون تغییر کند (حتی با تاخیر): اول تلاش می‌کنیم idهای خام
// را از روی optionsList فعلی resolve کنیم؛ اگر چیزی باقی ماند و apiUrl
// داریم، برای همان‌ها درخواست جدا می‌زنیم.
watch(model, (newVal) => {
    if (!props.multiple) return;
    if (!Array.isArray(newVal) || !newVal.length) return;

    resolveFromLocal();

    if (!props.apiUrl) return;

    const stillRawIds = (Array.isArray(model.value) ? model.value : [])
        .filter((item) => !(item && typeof item === 'object'));
    if (!stillRawIds.length) return;

    resolveRawIds(stillRawIds);
}, {immediate: true})

// هر بار optionsList پر/آپدیت شود (چه استاتیک، چه بعد از fetch)،
// دوباره روی model فعلی resolve محلی را امتحان می‌کند
watch(optionsList, () => {
    resolveFromLocal();
})

onMounted(() => {
    if (props.apiUrl) {
        fetchOptions();
    } else {
        if (Array.isArray(props.options)) {
            optionsList.value = props.options;
        } else {
            const options = [];
            const optionsUtils = Tools.utils2(props.options);
            optionsUtils.map((optionsUtil) => {
                options.push({
                    [props.valueKey]: optionsUtil.value,
                    [props.labelKey]: optionsUtil.label
                })
            });
            optionsList.value = options;
        }
    }
})

onBeforeUnmount(() => {
    if (debounceTimer) clearTimeout(debounceTimer);
})
</script>

<template>
    <div class="row">
        <div v-if="title" class="" :class="{'col-4 d-flex align-items-center': !colum, 'px-1': colum}">
            <label class="text-gray-dark fw-700" style="padding-bottom: 4px;">{{ title }}</label>
        </div>
        <div class="mx-0 px-0" :class="{'col-12': colum || !title, 'col-8': !colum}"
             style="padding: 0 14px !important;">
            <multiselect
                v-model="internalValue"
                :placeholder="placeholder"
                :label="labelKey"
                :track-by="valueKey"
                :options="optionsList"
                :multiple="multiple"
                :taggable="taggable"
                :loading="isLoading"
                :max="max"
                :internal-search="!asyncSearch"
                @search-change="onSearchChange"
                @tag="addTag"
                dir="rtl"
            ></multiselect>
        </div>
    </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style scoped>
.multiselect {
    direction: rtl;
    text-align: right;
}
</style>
