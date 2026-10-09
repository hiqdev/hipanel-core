import React from "react";
import { DatePicker, Flex, Input, Segmented, Select } from "antd";
import type { Filters, Scope } from "../types";
import { canSplitNested, colors } from "../utils";

export const FilterBar = ({ filters, users, onChange }: { filters: Filters; users: string[]; onChange: (f: Filters) => void }) => {
  const set = (patch: Partial<Filters>) => onChange({ ...filters, ...patch });

  return (
    <Flex gap={"small"} wrap align={"start"} style={{ marginBottom: 16 }}>
      <Input.Search
        allowClear
        placeholder={"Property, value, entity id or table"}
        style={{ width: 300 }}
        onChange={(e) => set({ search: e.target.value })}
      />
      <DatePicker.RangePicker
        allowClear
        onChange={(dates) => set({ range: dates?.[0] && dates?.[1] ? [dates[0].startOf("day").valueOf(), dates[1].endOf("day").valueOf()] : null })}
      />
      <Select
        mode={"multiple"}
        allowClear
        placeholder={"User"}
        style={{ minWidth: 180 }}
        options={users.map((login) => ({ label: login, value: login }))}
        onChange={(users) => set({ users })}
      />
      <Select
        mode={"multiple"}
        allowClear
        placeholder={"Operation"}
        style={{ minWidth: 180 }}
        options={Object.keys(colors).map((operation) => ({ label: operation.toUpperCase(), value: operation }))}
        onChange={(operations) => set({ operations })}
      />
      {canSplitNested && (
        <Segmented<Scope>
          className={"audit-filters"}
          value={filters.scope}
          options={[{ label: "All", value: "all" }, { label: "Direct", value: "direct" }, { label: "Nested", value: "nested" }]}
          onChange={(scope) => set({ scope })}
        />
      )}
    </Flex>
  );
};
